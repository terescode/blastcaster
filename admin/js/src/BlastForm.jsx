import React, {Component, PropTypes} from 'react';
import MuiThemeProvider from 'material-ui/styles/MuiThemeProvider';
import Paper from 'material-ui/Paper';
import TextField from 'material-ui/TextField';
import RaisedButton from 'material-ui/RaisedButton';
import CategoryPicker from './components/CategoryPicker';
import ImagePicker from './components/ImagePicker';
import TagPicker from './components/TagPicker';
import styles from './BlastForm.css';

export default class BlastForm extends Component {
  constructor(props) {
    super(props);
    var state = {
      title: '',
      description: '',
      url: '',
      prompt: '',
      categories: [],
      tags: []
    };
    this.action = this.props.data.action;
    this.actionNonce = this.props.data.action_nonce;
    if (this.props.data['bc-add-title']) {
      state.title = this.props.data['bc-add-title'];
    } else if (this.props.data.page_data.titles &&
        0 < this.props.data.page_data.titles.length) {
      state.title = this.props.data.page_data.titles[0];
    }
    if (this.props.data['bc-add-desc']) {
      state.description = this.props.data['bc-add-desc'];
    } else if (this.props.data.page_data.descriptions &&
        0 < this.props.data.page_data.descriptions.length) {
      state.description = this.props.data.page_data.descriptions[0];
    }
    if (this.props.data['bc-add-url']) {
      state.url = this.props.data['bc-add-url'];
    } else if (this.props.data.page_data.urls &&
        0 < this.props.data.page_data.urls.length) {
      state.url = this.props.data.page_data.urls[0];
    }
    if (this.props.data['bc-add-cat']) {
      state.categories = this.props.data['bc-add-cat'];
    }
    if (this.props.data['bc-add-tax']) {
      state.tags = this.props.data['bc-add-tax'];
    } else if (this.props.data.page_data.tags &&
        0 < this.props.data.page_data.tags.length) {
      state.tags = this.props.data.page_data.tags;
    }
    if (this.props.data['bc-add-prompt']) {
      state.prompt = this.props.data['bc-add-prompt'];
    }
    this.state = state;
  }

  render() {
    return (
      <MuiThemeProvider>
        <div className="wrap">
          <h1>Add a blast</h1>
          <Paper className={styles.wrapper} zDepth={2}>
            <p>Create a new blast using the fields below.</p>
            <form name="blastcaster-form" id="blastcaster-form" method="post" encType="multipart/form-data">
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
              <CategoryPicker categories={this.props.data.categories} defaultValue={this.state.categories} />
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
              <TagPicker tags={this.props.data.tags} defaultValue={this.state.tags} />
              <TextField
                hintText="Enter the URL for the link to the original article"
                floatingLabelText="Link URL"
                floatingLabelFixed={true}
                multiLine={false}
                rows={1}
                id="bc-add-url"
                name="bc-add-url"
                fullWidth={true}
                defaultValue={this.state.url}
              />
              <TextField
                hintText="Enter the text for the link to the original article"
                floatingLabelText="Link text"
                floatingLabelFixed={true}
                multiLine={false}
                rows={1}
                id="bc-add-prompt"
                name="bc-add-prompt"
                fullWidth={true}
                defaultValue={this.state.prompt}
              />
              <RaisedButton
                label="Add blast"
                primary={true}
                className={styles.submit}
                type="submit" />
            </form>
          </Paper>
        </div>
      </MuiThemeProvider>
    );
  }
}

BlastForm.propTypes = {
    data: PropTypes.object.isRequired,
};