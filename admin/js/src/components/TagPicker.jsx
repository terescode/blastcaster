import React, {Component, PropTypes} from 'react';
import ChipInput from 'material-ui-chip-input';
import styles from './TagPicker.css';

const dataSourceConfig = {
  text: 'name',
  value: 'term_id'
};

function isNumeric(val) {
  return !isNaN(parseFloat(val)) && isFinite(val);
}

function resolveSlug(idOrSlug, tags) {
  if (!isNumeric(idOrSlug)) {
    return { term_id: idOrSlug, name: idOrSlug };
  }
  for (var i = 0; i < tags.length; i += 1) {
    if (tags[i].term_id === Number(idOrSlug)) {
      return tags[i];
    }
  }
  return { term_id: idOrSlug, name: idOrSlug };
}

export default class TagPicker extends Component {
  constructor(props) {
    super(props);
    this.state = {
      tags: (
        this.props.defaultValue ?
        this.props.defaultValue.map( (idOrSlug) => resolveSlug(idOrSlug, this.props.tags) ) :
        []
      )
    };
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

  render() {
    return (
      <div className={styles.wrapper}>
        {this.state.tags.map( (tag) => {
          return (<input type="hidden" name="bc-add-tax[]" key={tag.term_id} value={tag.term_id} />);
        })}
        <ChipInput
          id="tag-chip-input"
          value={this.state.tags}
          dataSource={this.props.tags}
          dataSourceConfig={dataSourceConfig}
          hintText="Start typing to enter tags. The enter key completes a tag."
          floatingLabelText="Tags"
          floatingLabelFixed={true}
          fullWidth={true}
          fullWidthInput={true}
          onRequestAdd={(chip) => this.handleAddChip(chip)}
          onRequestDelete={(chip, index) => this.handleDeleteChip(chip, index)}
        />
      </div>
    );
  }
}

TagPicker.propTypes = {
  tags: PropTypes.array.isRequired,
  defaultValue: PropTypes.array
};