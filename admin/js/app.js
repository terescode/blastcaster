/* jshint esnext:true */
/* global document, terescode */
import React from 'react';
import ReactDOM from 'react-dom';
import injectTapEventPlugin from 'react-tap-event-plugin';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Paper from 'material-ui/Paper';
import TextField from 'material-ui/TextField';
import {Tabs, Tab} from 'material-ui/Tabs';
import {GridList, GridTile} from 'material-ui/GridList';
import Toggle from 'material-ui/Toggle';
import RaisedButton from 'material-ui/RaisedButton';
import {cyan50, cyan500, grey400} from 'material-ui/styles/colors.js';

const styles = {
  paperStyle: {
    margin: '10px 0px',
    padding: '10px 20px'
  },
  imgPickerStyle: {
    marginTop: '10px'
  },
  imgCarouselRoot: {
    display: 'flex',
    flexWrap: 'wrap',
    justifyContent: 'space-around',
    backgroundColor: cyan50
  },
  imgToggle: {
    marginTop: 16
  },
  noImagePanel: {
    backgroundColor: cyan50,
    textAlign: 'center',
	height: 180,
  },
  noImageHeader: {
	margin: 0,
	paddingTop: 60,
	fontWeight: 'normal',
	fontSize: '2em',
	letterSpacing: '-1px',
  },
  noImageSubheader: {
	fontWeight: 'normal',
	fontSize: '1em',
	letterSpacing: '0px',
  },
  uploadLink: {
    color: cyan500,
  },
  gridList: {
    display: 'flex',
    flexWrap: 'nowrap',
    overflowX: 'auto',
  },
  gridTile: {
    backgroundColor: 'transparent',
    width: '212px',
    padding: '0px 4px'
  },
  gridTileSelected: {
    backgroundColor: grey400,
    width: '212px',
    padding: '0px 4px'
  },
  tileImage: {
    transform: 'translateX(-50%) translateY(-50%)',
    top: '50%',
    width: '100%',
    height: 'auto',
  },
  submitButtonStyle: {
    margin: '12px 0px',
  }
};

class ImageTile extends React.Component {
  constructor(props) {
    super(props);
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick(evt) {
    if (this.props.onChange) {
      this.props.onChange(this.props.url);
    }
  }

  render() {
    return (
      <GridTile style={this.props.selected ? styles.gridTileSelected : styles.gridTile} onTouchTap={this.handleClick}>
        <img style={styles.tileImage} src={this.props.url} />
      </GridTile>
    );
  }
}

class ImageCarousel extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      tiles: props.images,
      selected: this.props.url || null
    };
    this.handleChange = this.handleChange.bind(this);
  }

  handleChange(url) {
    this.setState({ selected: url });
    if (this.props.onChange) {
      this.props.onChange(url);
    }
  }

  render() {
    return (
      <div style={styles.imgCarouselRoot}>
        <GridList style={styles.gridList} cols={2}>
          {this.state.tiles.map((tile) => {
            return (
              <ImageTile key={tile} url={tile} onChange={this.handleChange} selected={this.state.selected === tile} />
            );
          })}
        </GridList>
      </div>
    );
  }
}

class ImagePicker extends React.Component {
  constructor(props) {
    super(props);
    var type;
    this.state = {
      type: 'url',
      url: null,
      fileName: null,
      useImage: true
    };
    if (this.props.data['bc-add-image-type']) {
      type = this.props.data['bc-add-image-type'];
      if ('none' === type) {
        this.state.useImage = false;
      } else {
        this.type = type;
      }
    }
    if (this.props.data['bc-add-image-url']) {
      this.state.url = this.props.data['bc-add-image-url'];
    }
    this.handleTabChange = this.handleTabChange.bind(this);
    this.handleToggle = this.handleToggle.bind(this);
    this.handleUrlChange = this.handleUrlChange.bind(this);
    this.handleFileChange = this.handleFileChange.bind(this);
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
      value = (this.state.useImage ? ( this.state.type === 'file' ? 'file' : 'url' ) : 'none'),
      labelStyle = {
        color: this.context.muiTheme.textField.floatingLabelColor,
        fontSize: '12px',
        cursor: 'auto'
      };
    if (this.props.data.page_data.images &&
        0 < this.props.data.page_data.images.length) {
      recommended = <ImageCarousel images={this.props.data.page_data.images} url={this.state.url} onChange={this.handleUrlChange} />;
    } else {
      recommended = (
        <div className="image-picker__no-img" style={styles.noImagePanel}>
          <h2 style={styles.noImageHeader}>No recommended images</h2>
        </div>
      );
    }
    if (this.props.data.page_data.allImages &&
        0 < this.props.data.page_data.allImages.length) {
      all = <ImageCarousel images={this.props.data.page_data.allImages} url={this.state.url} onChange={this.handleUrlChange}  />;
    } else {
      all = (
        <div className="image-picker__no-img" style={styles.noImagePanel}>
          <h2 style={styles.noImageHeader}>No images</h2>
        </div>
      );
    }
    return (
      <div style={styles.imgPickerStyle}>
        <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
        <input type="hidden" name="bc-add-image-type" value={value} />
        <input type="hidden" name="bc-add-image-url" value={this.state.url || ''} />
        <label style={labelStyle}>Image</label>
        <Tabs
          value={this.state.type}
          onChange={this.handleTabChange}
        >
          <Tab label="Recommended" value="url">
            {recommended}
          </Tab>
          <Tab label="All" value="url2">
            {all}
          </Tab>
          <Tab label="Upload" value="file">
             <div className="image-picker__no-img image-picker__file-upload" style={styles.noImagePanel}>
               <input type="file" name="bc-add-image-file" id="bc-add-image-file" onChange={this.handleFileChange} />
               <h2 style={styles.noImageHeader}><label htmlFor="bc-add-image-file"><span style={styles.uploadLink}>Choose a file</span></label> to upload</h2>
               <h3 style={styles.noImageSubheader}>Currently selected: {this.state.fileName || 'None'}</h3>
            </div>
          </Tab>
        </Tabs>
        <Toggle
          label="Include image in blast"
          labelPosition="right"
          defaultToggled={this.state.useImage}
          style={styles.imgToggle}
          onToggle={this.handleToggle}
        />
      </div>
    );
  }
}

ImagePicker.contextTypes = {
  muiTheme: React.PropTypes.object
};

class BlastForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      title: '',
      description: ''
    };
    this.action = this.props.data.action;
    this.actionNonce = this.props.data.action_nonce;
    if (this.props.data['bc-add-title']) {
      this.state.title = this.props.data['bc-add-title'];
    } else if (this.props.data.page_data.titles &&
        0 < this.props.data.page_data.titles.length) {
      this.state.title = this.props.data.page_data.titles[0];
    }
    if (this.props.data['bc-add-desc']) {
      this.state.description = this.props.data['bc-add-desc'];
    } else if (this.props.data.page_data.descriptions &&
        0 < this.props.data.page_data.descriptions.length) {
      this.state.description = this.props.data.page_data.descriptions[0];
    }
  }

  render() {
    return (
      <MuiThemeProvider>
        <div className="wrap">
          <h1>Add a blast</h1>
          <Paper style={styles.paperStyle} zDepth={2}>
            <p>Create a new blast using the fields below.</p>
            <form name="blastcaster-form" id="blastcaster-form" method="post" type="multipart/form-data">
              <input type="hidden" name="action" value={this.action} />
              <input type="hidden" name={this.action + '_nonce'} value={this.actionNonce} />
              <input type="hidden" name="pageData" value={JSON.stringify(this.props.data.page_data)} />
              <TextField
                hintText="Enter a title for the blast"
                floatingLabelText="Title"
                floatingLabelFixed={true}
                multiLine={true}
                rows={1}
                rowsMax={3}
                id="bc-add-title"
                name="bc-add-title"
                fullWidth={true}
                defaultValue={this.state.title}
              />
              <ImagePicker data={this.props.data} />
              <TextField
                hintText="Enter a description for the blast"
                floatingLabelText="Description"
                floatingLabelFixed={true}
                multiLine={true}
                rows={1}
                rowsMax={4}
                id="bc-add-desc"
                name="bc-add-desc"
                fullWidth={true}
                defaultValue={this.state.description}
              />
              <RaisedButton
                label="Add blast"
                primary={true}
                style={styles.submitButtonStyle}
                type="submit" />
            </form>
          </Paper>
        </div>
      </MuiThemeProvider>
    );
  }
}

injectTapEventPlugin();
ReactDOM.render(
  <BlastForm data={terescode.bc_data} />,
  document.getElementById('blastcaster-root')
);