import React, {ChangeEvent, Component, createRef} from "react";
import Button from "./Button";
import Axios, {AxiosResponse} from "axios";
import Dialog from "./Dialog";
import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

export interface ImageUploaderProps {
  placeholder: string;
  endpoint: string;
}

class ImageUploader extends Component<ImageUploaderProps, {}> {
  state = {
    progress: 0,
    displayCropper: false,
    uploadedURL: "",
    loadingCropper: false
  };

  constructor(props: ImageUploaderProps) {
    super(props);
    this.selectPicture = this.selectPicture.bind(this);
  }

  private uploader = createRef<HTMLInputElement>();

  render() {
    return (
        <div style={{textAlign: "center", padding: "5px"}}>
          {this.state.progress > 0 && this.state.progress < 100 && (
              <div className="upload-status">
                <span style={{margin: "5px"}}> {this.props.placeholder} </span>
                <div className="upload-meter">
                  <span style={{width: this.state.progress + "%"}}/>
                </div>
              </div>
          )}

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
          {this.state.displayCropper && (
              <Dialog
                  heading="Adjust the image"
                  content={this.renderCropper()}
                  closable
                  overlayed={false}
              />
          )}
        </div>
    );
  }

  onChangeFile(event: ChangeEvent) {
    event.stopPropagation();
    event.preventDefault();
    const target = event.target as HTMLInputElement;
    const file: File = (target.files as FileList)[0];
    console.log(file);
    this.upload(file);
  }

  private upload(file: File) {
    let form = new FormData();
    form.append("avatar", file);
    Axios({
      method: "post",
      url: this.props.endpoint,
      data: form,
      onUploadProgress: function (progressEvent: any) {
        let percentCompleted = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
        );
        this.setState({progress: percentCompleted});
      }.bind(this),
      headers: {"Content-Type": "multipart/form-data"}
    })
        .then(
            function (response: AxiosResponse) {
              this.adjustImage(response.data.url);
              // console.log(response.data.url);
            }.bind(this)
        )
        .catch(function (response) {
          console.log(response);
        });
  }

  private adjustImage(url: string) {
    this.setState({
      loadingCropper: true,
      displayCropper: true,
      uploadedURL: url
    });
    const image = document.getElementById("new-profile-pic");

    let removeLoader = () => {
      this.setState({loadingCropper: false});
    };

    const cropper = new Cropper(image as HTMLImageElement, {
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
        console.log(event.detail.x);
      },
      ready(event: CustomEvent<any>): void {
        this.cropper.setCropBoxData({top: 40, width: 320});
        removeLoader();
      }
    });
  }

  private selectPicture() {
    const node = this.uploader.current;
    if (node) node.click();
  }

  renderCropper() {
    return (
        <div className="img-editor-container">
          <div className="img-editor">
            <img
                className="canvas"
                alt="Profile Image"
                src={this.state.uploadedURL}
                id="new-profile-pic"
            />
            {this.state.loadingCropper && (
                <img
                    alt="loading"
                    style={{
                      position: "absolute", top: "50%", left: "50%",
                      transform: "translate(-50%, 0)"
                    }}
                    src={process.env.img_assets + "/loader-64.gif"}
                />
            )}
          </div>
        </div>
    );
  }
}

export default ImageUploader;
