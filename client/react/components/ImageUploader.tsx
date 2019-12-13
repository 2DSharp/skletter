import React, {ChangeEvent, Component, createRef} from "react";
import ReactDOM from "react-dom";
import Button from "./Button";
import Axios, {AxiosResponse} from "axios";
import Dialog from "./Dialog";
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";
import * as noUiSlider from "nouislider";
import "nouislider/distribute/nouislider.min.css";
import DecisionButtonGroup from "./DecisionButtonGroup";
import ProgressMeter from "./ProgressMeter";

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

  private uploader = createRef<HTMLInputElement>();
  private imgRef = createRef<HTMLImageElement>();
  private zoomSlider = createRef<HTMLDivElement>();
  private cropper: Cropper;

  constructor(props: ImageUploaderProps) {
    super(props);
    this.selectPicture = this.selectPicture.bind(this);
  }

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
          {!this.state.transactionCompleted &&
          this.state.displayCropper &&
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
    this.setState({
      file: file,
      transactionCompleted: false,
      uploading: false,
      progress: 0,
      loadingCropper: true,
      displayCropper: true,
      uploadedURL: URL.createObjectURL(file)
    });
  }

  private upload() {
    console.log(this.cropper.getCanvasData());
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
              this.setState({transactionCompleted: true, displayCropper: false});
            }.bind(this)
        )
        .catch(function (response) {
          console.log(response);
        });
  }

  private adjustImage() {
    const image: any = this.imgRef.current;
    // Let the image load first and then change to avoid cached/inconsistent behavior:
    // https://css-tricks.com/measuring-image-widths-javascript-carefully/
    image.addEventListener("load", function () {
      // Fit image to container based on the smaller side
      const side =
          image.naturalWidth < image.naturalHeight ? "width" : "height";
      image.style[side] = "400px";
    });

    const removeLoader = () => {
      this.setState({loadingCropper: false});
    };
    const slider = this.zoomSlider.current;
    let minZoom: number = 0;
    let maxZoom: number = 1;

    this.cropper = new Cropper(image as HTMLImageElement, {
      aspectRatio: 1,
      background: false,
      cropBoxMovable: false,
      viewMode: 1,
      cropBoxResizable: false,
      highlight: false,
      guides: false,
      center: false,
      toggleDragModeOnDblclick: false,
      dragMode: "move",
      ready(event: CustomEvent<any>): void {
        this.cropper.setCropBoxData({top: 0, left: this.cropper.getCanvasData().width * 0.5 - 385 / 2, width: 385});
        const imageData = this.cropper.getImageData();
        //if (imageData.width > imageData.height)
        minZoom = (imageData.width / imageData.naturalWidth) - 0.05;
        //else
        const ratio = Math.floor(imageData.naturalWidth / imageData.width);
        if (2 >= ratio)
          maxZoom = 2;
        else if (5 < ratio)
          maxZoom = 0.5;

        console.log("Width: " + (imageData.naturalWidth / imageData.width));
        console.log("Max zoom: " + maxZoom);

        let rangeSlider: noUiSlider.noUiSlider = noUiSlider.create(slider, {
          start: 0,
          connect: [true, false],
          range: {
            min: minZoom,
            max: maxZoom,
          },
          behaviour: 'tap-drag',
          step: 0.0001
        });
        console.log(minZoom);
        removeLoader();
        rangeSlider.on(
            "update",
            function (values: any, handle: any) {
              console.log(rangeSlider.get());
              const containerData = this.cropper.getContainerData();
              this.cropper.zoomTo(((rangeSlider.get() as unknown) as number), {
                x: containerData.width / 2,
                y: containerData.height / 2
              });
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
              <ProgressMeter
                  progress={this.state.progress}
                  placeholder={this.props.placeholder}
              />
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
              <div id="img-zoom-slider" ref={this.zoomSlider}/>
              <span className="fas fa-image zoom-tip in"/>
            </div>
            <DecisionButtonGroup
                positiveAction={this.upload.bind(this)}
                negativeAction={this.closeWindow.bind(this)}
                positiveText="Apply Changes"
                negativeText="Cancel"
            />
          </React.Fragment>
      );
  }

  closeWindow() {
    this.setState({transactionCompleted: true, displayCropper: false});
  }

  renderCropper() {
    let containerStyle = {opacity: 1};
    if (this.state.uploading) containerStyle = {opacity: 0.4};

    let placeholderStyle = {};
    if (!this.state.loadingCropper && !this.state.uploading) {
      placeholderStyle = {display: "none"};
    }

    const ImagePlaceholder = React.forwardRef((props: any, ref: any) => (
        <img
            className="canvas"
            alt="Profile Image"
            ref={ref}
            style={placeholderStyle}
            src={this.state.uploadedURL}
            id="new-profile-pic"
        />
    ));
    return (
        <React.Fragment>
          {this.displayProgress()}
          <div className="editor-prompt">
            <div style={containerStyle} className="img-editor-container">
              <div className="img-editor">
                <ImagePlaceholder ref={this.imgRef}/>
              </div>
            </div>
            {this.showControls()}
          </div>
        </React.Fragment>
    );
  }
}

export default ImageUploader;