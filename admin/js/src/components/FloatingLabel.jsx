import React, {Component, PropTypes} from 'react';
import styles from './FloatingLabel.css';

export default class FloatingLabel extends Component {
  constructor(props) {
    super(props);
  }

  componentWillMount() {
    this.labelColor = {
        color: this.context.muiTheme.textField.floatingLabelColor
    };
  }

  render() {
    return <label className={styles.wrapper} style={this.labelColor}>{this.props.label}</label>;
  }
}

FloatingLabel.propTypes = {
  label: PropTypes.string.isRequired
};

FloatingLabel.contextTypes = {
  muiTheme: PropTypes.object
};