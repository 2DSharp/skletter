import React, {ChangeEvent, Component, createRef} from "react";
import ReactDOM from "react-dom";
import Button from "./Button";
import Axios, {AxiosResponse} from "axios";
import Dialog from "./Dialog";
import "cropperjs/dist/cropper.css";
import * as noUiSlider from "nouislider";
import "nouislider/distribute/nouislider.min.css";
import DecisionButtonGroup from "./DecisionButtonGroup";
import ProgressMeter from "./ProgressMeter";
import ReactAvatarEditor, {Position} from "react-avatar-editor";

export interface ImageUploaderProps {
  placeholder: string;
  endpoint: string;
}

export interface ImageUploaderState {
  progress: number;
  displayCropper: boolean;
  uploading: boolean;
  transactionCompleted: boolean;
  image: File;
  showLoader: boolean;
  allowZoomOut: boolean;
  position: Position;
  scale: number;
  rotate: number;
  borderRadius: number;
  preview: any;
  width: number;
  height: number;
  naturalWidth?: number;
  naturalHeight?: number;
}

export const getOrientation = (file: File, callback: Function) => {
  const reader = new FileReader();

  reader.onload = (event: ProgressEvent) => {
    if (!event.target) {
      return;
    }

    const file = event.target as FileReader;
    const view = new DataView(file.result as ArrayBuffer);

    if (view.getUint16(0, false) != 0xffd8) {
      return callback(-2);
    }

    const length = view.byteLength;
    let offset = 2;

    while (offset < length) {
      if (view.getUint16(offset + 2, false) <= 8) return callback(-1);
      let marker = view.getUint16(offset, false);
      offset += 2;

      if (marker == 0xffe1) {
        if (view.getUint32((offset += 2), false) != 0x45786966) {
          return callback(-1);
        }

        let little = view.getUint16((offset += 6), false) == 0x4949;
        offset += view.getUint32(offset + 4, little);
        let tags = view.getUint16(offset, little);
        offset += 2;
        for (let i = 0; i < tags; i++) {
          if (view.getUint16(offset + i * 12, little) == 0x0112) {
            return callback(view.getUint16(offset + i * 12 + 8, little));
          }
        }
      } else if ((marker & 0xff00) != 0xff00) {
        break;
      } else {
        offset += view.getUint16(offset, false);
      }
    }
    return callback(-1);
  };

  reader.readAsArrayBuffer(file);
};
class ImageUploader extends Component<ImageUploaderProps, ImageUploaderState> {
  state: ImageUploaderState = {
    progress: 0,
    displayCropper: false,
    uploading: false,
    transactionCompleted: false,
    image: null,
    showLoader: false,
    allowZoomOut: false,
    position: {x: 0.5, y: 0.5},
    scale: 1,
    rotate: 0,
    borderRadius: 50,
    preview: null,
    width: 310,
    height: 310
  };

  private uploader = createRef<HTMLInputElement>();
  private zoomSlider = createRef<HTMLDivElement>();
  private editor: ReactAvatarEditor;

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
  }

  onChangeFile(event: ChangeEvent) {
    event.stopPropagation();
    event.preventDefault();
    const target = event.target as HTMLInputElement;
    this.preparePreview();
    this.handleNewImage(event);
  }

  private preparePreview() {
    this.setState({
      transactionCompleted: false,
      uploading: false,
      progress: 0,
      showLoader: true,
      displayCropper: true
    });
  }

  private upload() {
    this.setState({uploading: true});
    const image = this.editor.getImage();
    const rect = this.editor.getCroppingRect();
    let croppedData: object;

    croppedData = {
      x: Math.round(this.state.naturalWidth * rect.x),
      y: Math.round(this.state.naturalHeight * rect.y),
      width: Math.round(image.width * rect.width * this.state.scale),
      height: Math.round(image.height * rect.height * this.state.scale)
    };

    console.log(croppedData);

    let form = new FormData();
    form.append("avatar", this.state.image);
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
            <div style={{marginTop: "10px", textAlign: "center"}}>
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

  private adjustImage() {
    const slider = this.zoomSlider.current;

    let rangeSlider: noUiSlider.noUiSlider = noUiSlider.create(slider, {
      start: 0,
      connect: [true, false],
      range: {
        min: 1,
        max: 2
      },
      step: 0.01
    });

    rangeSlider.on(
        "update",
        function (values: any, handle: any) {
          this.handleScale(parseFloat(values[0]));
        }.bind(this)
    );
  }

  setEditorRef = (editor: ReactAvatarEditor) => (this.editor = editor);

  displayLoader() {
    return this.state.showLoader &&
        <img className="centered" style={{zIndex: 1000}} src={process.env.img_assets + "/loader-64.gif"} alt="Loading"/>
  }

  renderCropper() {
    let containerStyle = {opacity: 1};
    if (this.state.uploading) containerStyle = {opacity: 0};

    return (
        <>
          {this.displayProgress()}
          <div className="editor-prompt">
            <div style={containerStyle} className="img-editor-container">
              <div className="img-editor">

                <div style={{margin: "0 auto", textAlign: "center"}}>
                  {this.displayLoader()}
                  <ReactAvatarEditor
                      ref={this.setEditorRef}
                      scale={this.state.scale}
                      width={this.state.width}
                      height={this.state.height}
                      style={{cursor: "move"}}
                      color={[245, 245, 245, 0.6]} // RGBA
                      position={this.state.position}
                      onPositionChange={this.handlePositionChange}
                      rotate={this.state.rotate}
                      borderRadius={
                        this.state.width / (100 / this.state.borderRadius)
                      }
                      image={this.state.image}
                      className="editor-canvas"
                  />
                </div>
              </div>
            </div>

            {this.showControls()}
          </div>
        </>
    );
  }

  handleNewImage = (e: ChangeEvent) => {
    const file: any = (e.target as HTMLInputElement).files[0];

    getOrientation(
        file,
        function (orientation: number) {
          switch (orientation) {
            case 3:
              this.setState({rotate: 180});
              break;
            case 6:
              this.setState({
                rotate: 90,
                width: this.state.height,
                height: this.state.width
              });
              break;
            case 8:
              this.setState({
                rotate: -90
              });
              break;
            default:
              this.setState({rotate: 0});
              break;
          }
        }.bind(this)
    );
    this.setState({image: file});
    let fr = new FileReader();

    fr.onload = function () {
      let img = new Image();
      img.onload = function () {
        this.setState({
          naturalWidth: img.width,
          naturalHeight: img.naturalHeight,
          showLoader: false
        });
      }.bind(this);
      if (typeof fr.result === "string") {
        img.src = fr.result;
      } // is the data URL because called with readAsDataURL
    }.bind(this);

    fr.readAsDataURL(file);
  };

  handleScale = (value: number) => {
    const scale = value;
    this.setState({scale});
  };

  handlePositionChange = (position: Position) => {
    this.setState({position});
  };
}

export default ImageUploader;
