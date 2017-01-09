import React from 'react';
import ChipList from './ChipList';
import styles from './TagPicker.css';

export default class TagPicker extends React.Component {

  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div className={styles.wrapper}>
        <ChipList />
      </div>
    );
  }
}