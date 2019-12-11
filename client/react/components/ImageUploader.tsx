import React, {ChangeEvent, Component, createRef} from "react";
import ReactDOM from "react-dom";
import Button from "./Button";
import Axios, {AxiosResponse} from "axios";
import Dialog from "./Dialog";
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";
import * as noUiSlider from "nouislider";
import "nouislider/distribute/nouislider.min.css";

export interface ImageUploaderProps {
  placeholder: string;
  endpoint: string;
}

export interface ImageUploaderState {
  progress: number;
  displayCropper: boolean;
  uploadedURL: string;
  loadingCropper: boolean;
  file: File;
  uploading: boolean;
  transactionCompleted: boolean;
}

class ImageUploader extends Component<ImageUploaderProps, ImageUploaderState> {
  state: ImageUploaderState = {
    progress: 0,
    displayCropper: false,
    uploadedURL: "",
    loadingCropper: false,
    uploading: false,
    file: null,
    transactionCompleted: false
  };

  constructor(props: ImageUploaderProps) {
    super(props);
    this.selectPicture = this.selectPicture.bind(this);
  }

  private uploader = createRef<HTMLInputElement>();

  render() {
    return (
        <div style={{textAlign: "center", padding: "5px"}}>
          <Button
              bindClass="std primary-btn medium upload-btn"
              type="action"
              action={this.selectPicture}
          >
            <span className="fas fa-upload icon spaced"/>
            Upload Image
          </Button>
          <input
              id="uploader"
              type="file"
              name="avatar"
              accept="image/*"
              ref={this.uploader}
              style={{display: "none"}}
              onChange={this.onChangeFile.bind(this)}
          />
          {!this.state.transactionCompleted && this.state.displayCropper &&
          ReactDOM.createPortal(
              <Dialog
                  heading="Adjust the image"
                  content={this.renderCropper()}
                  closable
                  overlayed={false}
              />,
              document.getElementById("dialog-root")
          )}
        </div>
    );
  }

  componentDidUpdate(
      prevProps: ImageUploaderProps,
      prevState: ImageUploaderState
  ) {
    if (!prevState.displayCropper && this.state.displayCropper) {
      this.adjustImage();
    }
    if (!prevState.uploading && this.state.uploading) {
      this.cropper.destroy();
    }
  }

  onChangeFile(event: ChangeEvent) {
    event.stopPropagation();
    event.preventDefault();
    const target = event.target as HTMLInputElement;
    const file: File = (target.files as FileList)[0];
    this.preparePreview(file);
  }

  private preparePreview(file: File) {
    this.setState({progress: 100, file: file});
    this.prepareCanvas(URL.createObjectURL(file));
  }

  private upload() {
    this.setState({uploading: true});
    let form = new FormData();
    form.append("avatar", this.state.file);
    Axios({
      method: "post",
      url: this.props.endpoint,
      data: form,
      onUploadProgress: function (progressEvent: any) {
        let percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
        );
        this.setState({uploading: true});
        this.setState({progress: percentCompleted});
      }.bind(this),
      headers: {"Content-Type": "multipart/form-data"}
    })
        .then(
            function (response: AxiosResponse) {
              this.setState({transactionCompleted: true});
            }.bind(this)
        )
        .catch(function (response) {
          console.log(response);
        });
  }

  private prepareCanvas(url: string) {
    this.setState({
      loadingCropper: true,
      displayCropper: true,
      uploadedURL: url
    });
  }

  private cropper: Cropper;
  private adjustImage() {
    const image: any = document.getElementById("new-profile-pic");
    // Let the image load first and then change to avoid cached/inconsistent behavior:
    // https://css-tricks.com/measuring-image-widths-javascript-carefully/
    image.addEventListener("load", function () {
      // Fit image to container based on the smaller side
      let side = image.naturalWidth < image.naturalHeight ? "width" : "height";
      image.style[side] = "320px";
    });

    let removeLoader = () => {
      this.setState({loadingCropper: false});
    };
    let slider = document.getElementById("img-zoom-slider");

    let rangeSlider: noUiSlider.noUiSlider = noUiSlider.create(slider, {
      start: 0,
      connect: [true, false],
      range: {
        min: 0.45,
        max: 1.5
      }
    });
    this.cropper = new Cropper(image as HTMLImageElement, {
      aspectRatio: 1,
      background: false,
      cropBoxMovable: false,
      viewMode: 1,
      cropBoxResizable: false,
      highlight: false,
      guides: false,
      center: false,
      dragMode: "move",
      crop(event) {
        //console.log(event.detail.x);
      },
      ready(event: CustomEvent<any>): void {
        this.cropper.setCropBoxData({top: 40, width: 320});
        removeLoader();
        rangeSlider.on(
            "update",
            function (values: any, handle: any) {
              this.cropper.zoomTo((rangeSlider.get() as unknown) as number);
            }.bind(this)
        );
      }
    });
  }

  private selectPicture() {
    const node = this.uploader.current;
    if (node) node.click();
  }

  displayProgress() {
    return (
        this.state.progress > 0 &&
        this.state.progress < 100 && (
            <div
                style={{
                  opacity: 1,
                  position: "absolute",
                  zIndex: 20,
                  top: "190px",
                  left: "50%",
                  transform: "translate(-50%, 0)"
                }}
                className="upload-status"
            >
          <span style={{margin: "5px", fontWeight: "bold"}}>
            {this.props.placeholder}
          </span>
              <div className="upload-meter">
                <span style={{width: this.state.progress + "%"}}/>
              </div>
            </div>
        )
    );
  }

  showControls() {
    if (this.state.uploading)
      return (
          <div style={{marginTop: "20px", textAlign: "center"}}>
          <span style={{margin: "5px", fontWeight: "bold"}}>
            Applying changes...
          </span>
          </div>
      );
    else
      return (
          <React.Fragment>
            <div style={{marginTop: "15px", textAlign: "center"}}>
              <span className="fas fa-image zoom-tip out"/>
              <div id="img-zoom-slider"/>
              <span className="fas fa-image zoom-tip in"/>
            </div>
            <div style={{textAlign: "center"}}>
              <Button
                  bindClass="confirmation negative spaced"
                  type="action"
                  action={() => console.log("hello")}
              >
                <span>Cancel</span>
              </Button>
              <Button
                  bindClass="confirmation positive spaced"
                  type="action"
                  action={this.upload.bind(this)}
              >
                <span>Apply changes</span>
              </Button>
            </div>
          </React.Fragment>
      );
  }

  renderCropper() {
    let containerStyle = {opacity: 1};
    if (this.state.uploading) containerStyle = {opacity: 0.4};

    return (
        <React.Fragment>
          {this.displayProgress()}
          <div className="editor-prompt">
            <div style={containerStyle} className="img-editor-container">
              <div className="img-editor">
                <img
                    className="canvas"
                    alt="Profile Image"
                    src={this.state.uploadedURL}
                    id="new-profile-pic"
                />
              </div>
            </div>
            {this.showControls()}
          </div>
        </React.Fragment>
    );
  }
}

export default ImageUploader;
