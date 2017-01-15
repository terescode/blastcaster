import React, {Component, PropTypes} from 'react';
import FloatingLabel from './FloatingLabel';
import {Table, TableBody, TableRow, TableRowColumn} from 'material-ui/Table';
import styles from './CategoryPicker.css';

class CategoryPickerRow extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { children, term, name, ...otherProps } = this.props;
    return (
      <TableRow {...otherProps}>
        {children}
        <TableRowColumn className={styles.cell}>
          {this.props.selected && <input type="hidden" name="bc-add-cat[]" value={term} />}
          {name}
        </TableRowColumn>
      </TableRow>
    );
  }
}

CategoryPickerRow.propTypes = {
    children: PropTypes.node,
    name: PropTypes.string.isRequired,
    term: PropTypes.number.isRequired,
    selected: PropTypes.bool
};

export default class CategoryPicker extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return (
      <div className={styles.wrapper}>
        <FloatingLabel label='Categories' />
        <Table
          height='150px'
          selectable={true}
          multiSelectable={true}
        >
          <TableBody
            displayRowCheckbox={true}
            deselectOnClickaway={false}
          >
            {this.props.categories.map( (row, index) => {
              return (<CategoryPickerRow className={styles.row} key={index} name={row.name} term={row.term_id} />);}
            )}
          </TableBody>
        </Table>
      </div>
    );
  }
}

CategoryPicker.propTypes = {
    categories: PropTypes.array.isRequired,
};