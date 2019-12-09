import React, {ChangeEvent, Component, createRef} from "react";
import Button from "./Button";
import Axios from "axios";

export interface ImageUploaderProps {
  placeholder: string;
  endpoint: string;
}

class ImageUploader extends Component<ImageUploaderProps, {}> {
  state = {
    progress: 0
  };

  constructor(props: ImageUploaderProps) {
    super(props);
    this.selectPicture = this.selectPicture.bind(this);
  }

  private uploader = createRef<HTMLInputElement>();

  render() {
    return (
        <div style={{textAlign: "center", padding: "5px"}}>
          <div
              style={{
                display:
                    this.state.progress > 0 && this.state.progress < 100
                        ? "block"
                        : "none"
              }}
              className="upload-status"
          >
            <span style={{margin: "5px"}}> {this.props.placeholder} </span>
            <div className="upload-meter">
              <span style={{width: this.state.progress + "%"}}/>
            </div>
          </div>

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
        .then(function (response) {
          console.log(response);
        })
        .catch(function (response) {
          console.log(response);
        });
  }

  private selectPicture() {
    const node = this.uploader.current;
    if (node) node.click();
  }
}

export default ImageUploader;
