import React, {Component, PropTypes} from 'react';
import FloatingLabel from './FloatingLabel';
import {Table, TableBody, TableRow, TableRowColumn} from 'material-ui/Table';
import styles from './CategoryPicker.css';

class CategoryPickerRow extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { children, term, name, fieldName, ...otherProps } = this.props;
    return (
      <TableRow {...otherProps}>
        {children}
        <TableRowColumn className={styles.cell}>
          {this.props.selected && <input type="hidden" name={fieldName} value={term} />}
          {name}
        </TableRowColumn>
      </TableRow>
    );
  }
}

CategoryPickerRow.propTypes = {
    children: PropTypes.node,
    name: PropTypes.string.isRequired,
    fieldName: PropTypes.string.isRequired,
    term: PropTypes.number.isRequired,
    selected: PropTypes.bool
};

function isSelected(term, terms) {
  for (var i = 0; i < terms.length; i += 1) {
    if (term.term_id === Number(terms[i])) {
      return true;
    }
  }
  return false;
}

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
              var selected = (this.props.defaultValue ? isSelected(row, this.props.defaultValue) : false);
              return (<CategoryPickerRow className={styles.row} key={index} fieldName={this.props.fieldName} name={row.name} term={row.term_id} selected={selected} />);}
            )}
          </TableBody>
        </Table>
      </div>
    );
  }
}

CategoryPicker.propTypes = {
  fieldName: PropTypes.string.isRequired,
  categories: PropTypes.array.isRequired,
  defaultValue: PropTypes.array
};