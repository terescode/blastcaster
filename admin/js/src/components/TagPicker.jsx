/* eslint-env browser, es6 */
import React, {Component, PropTypes} from 'react';
import ChipInput from 'material-ui-chip-input';
import styles from './TagPicker.css';

export default class TagPicker extends Component {
  constructor(props) {
    super(props);
    this.autoCompleteMin = props.autoCompleteMin || 2;
    this.autoCompleteDelay = props.autoCompleteDelay || 300;
    this.state = {
      tags: this.props.defaultValue || [],
      menuOpen: false,
      suggestions: []
    };
  }

  suggestionComplete(err, response) {
    if (err) {
      // TODO: ?
      return;
    }

    if (!response.ok) {
      // TODO: ?
      return;
    }

    response.text().then((text) => {
      var sugg = text.split(/[\r\n]/).map(function (string) {
        return string.trim();
      });
      this.setState( {
        menuOpen: 0 < sugg.length,
        suggestions: sugg
      });
    });
  }

  fetchSuggestions(searchText) {
    this.suggestResponse =
      fetch(
        this.props.autoCompleteUrl.replace(/[$][{]searchText[}]/g, searchText),
        {
          method: 'GET',
          redirect: 'follow',
          credentials: 'same-origin'
        }
      ).then(
        (response) => this.suggestionComplete(null, response),
        (error) => this.suggestionComplete(error)
      );
  }

  handleAddChip(chip) {
    this.setState({
      tags: this.state.tags.concat(chip)
    });
  }

  handleDeleteChip(chip, index) {
    this.state.tags.splice(index, 1);
    this.setState({
      tags: this.state.tags
    });
  }

  handleUpdateInput(searchText, dataSource, params) {
      if ('change' === params.source && this.props.autoCompleteUrl) {
        if (this.timeoutId) {
          clearTimeout(this.timeoutId);
        }
        if (this.autoCompleteMin <= searchText.length) {
          this.timeoutId = setTimeout(
            () => this.fetchSuggestions(searchText),
            this.autoCompleteDelay
          );
        } else {
          this.setState({ menuOpen: false, suggestions: [] });
        }
      }
  }

  render() {
    return (
      <div className={styles.wrapper}>
        {this.state.tags.map( (tag, index) => {
          return (<input type="hidden" name={this.props.fieldName} key={index} value={tag} />);
        })}
        <ChipInput
          id="tag-chip-input"
          value={this.state.tags}
          open={this.state.menuOpen}
          dataSource={this.state.suggestions}
          hintText="Start typing to enter tags. The enter key completes a tag."
          floatingLabelText="Tags"
          floatingLabelFixed={true}
          fullWidth={true}
          fullWidthInput={true}
          onRequestAdd={(chip) => this.handleAddChip(chip)}
          onRequestDelete={(chip, index) => this.handleDeleteChip(chip, index)}
          onUpdateInput={(searchText, dataSource, params) => this.handleUpdateInput(searchText, dataSource, params)}
        />
      </div>
    );
  }
}

TagPicker.propTypes = {
  defaultValue: PropTypes.array,
  fieldName: PropTypes.string.isRequired,
  autoCompleteUrl: PropTypes.string,
  autoCompleteMin: PropTypes.number,
  autoCompleteDelay: PropTypes.number
};
