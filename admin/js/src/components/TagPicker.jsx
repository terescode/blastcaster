import React, {Component, PropTypes} from 'react';
import ChipInput from 'material-ui-chip-input';
import styles from './TagPicker.css';

const dataSourceConfig = {
  text: 'name',
  value: 'term_id'
};

export default class TagPicker extends Component {
  constructor(props) {
    super(props);
    this.state = {
      tags: []
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
          return (<input type="hidden" name="bc-add-tax[]" value={tag.term_id} />);
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
  tags: PropTypes.array.isRequired
};