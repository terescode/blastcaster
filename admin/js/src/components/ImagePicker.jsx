import React, {Component, PropTypes} from 'react';
import {Tabs, Tab} from 'material-ui/Tabs';
import Toggle from 'material-ui/Toggle';
import ImageCarousel from './ImageCarousel';
import FloatingLabel from './FloatingLabel';
import styles from './ImagePicker.css';
import {cyan50, cyan500} from 'material-ui/styles/colors.js';

const inlineStyles = {
  noImage: {
    backgroundColor: cyan50
  },
  uploadLink: {
    color: cyan500
  }
};

export default class ImagePicker extends Component {
  constructor(props) {
    super(props);
    var type,
      state = {
      type: 'url',
      url: null,
      fileName: null,
      useImage: true
    };
    if (this.props.data['bc-add-image-type']) {
      type = this.props.data['bc-add-image-type'];
      if ('none' === type) {
        state.useImage = false;
      } else {
        this.type = type;
      }
    }
    if (this.props.data['bc-add-image-url']) {
      state.url = this.props.data['bc-add-image-url'];
    }
    this.state = state;
  }

  handleTabChange(type) {
    this.setState({
      type: type
    });
  }

  handleToggle(event, isInputChecked) {
    this.setState({
      useImage: isInputChecked
    });
  }

  handleUrlChange(url) {
    this.setState({
      url: url
    });
  }

  handleFileChange(evt) {
    var value = evt.target.value;
    if (value) {
      value = value.replace(/^(.+)[\/\\]([^\/\\]+)$/, '$2');
    }
    this.setState({
      fileName: value || null
    });
  }

  render() {
    var recommended,
      all,
      value = (this.state.useImage ? ( this.state.type === 'file' ? 'file' : 'url' ) : 'none');
    if (this.props.data.page_data.images &&
        0 < this.props.data.page_data.images.length) {
      recommended = <ImageCarousel images={this.props.data.page_data.images} url={this.state.url} onChange={(url) => this.handleUrlChange(url)} />;
    } else {
      recommended = (
        <div className={styles['no-image']}>
          <h2>No recommended images</h2>
        </div>
      );
    }
    if (this.props.data.page_data.allImages &&
        0 < this.props.data.page_data.allImages.length) {
      all = <ImageCarousel images={this.props.data.page_data.allImages} url={this.state.url} onChange={(url) => this.handleUrlChange(url)}  />;
    } else {
      all = (
        <div className={styles['no-image']} style={inlineStyles.noImage}>
          <h2>No images</h2>
        </div>
      );
    }
    return (
      <div className={styles.wrapper}>
        <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="3145728" />
        <input type="hidden" name="bc-add-image-type" value={value} />
        <input type="hidden" name="bc-add-image-url" value={this.state.url || ''} />
        <FloatingLabel label="Image" />
        <Tabs
          value={this.state.type}
          onChange={(type) => this.handleTabChange(type)}
        >
          <Tab label="Recommended" value="url">
            {recommended}
          </Tab>
          <Tab label="All" value="url2">
            {all}
          </Tab>
          <Tab label="Upload" value="file">
             <div className={styles['no-image']} style={inlineStyles.noImage}>
               <input type="file" name="bc-add-image-file" id="bc-add-image-file" onChange={(e) => this.handleFileChange(e)} />
               <h2><label htmlFor="bc-add-image-file"><span className={styles['upload-link']} style={inlineStyles.uploadLink}>Choose a file</span></label> to upload</h2>
               <h3>Currently selected: {this.state.fileName || 'None'}</h3>
            </div>
          </Tab>
        </Tabs>
        <Toggle
          label="Include image in blast"
          labelPosition="right"
          defaultToggled={this.state.useImage}
          className={styles['img-toggle']}
          onToggle={(e, checked) => this.handleToggle(e, checked)}
        />
      </div>
    );
  }
}

ImagePicker.propTypes = {
    data: PropTypes.object.isRequired,
};
