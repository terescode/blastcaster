import React, {Component, PropTypes} from 'react';
import {GridList, GridTile} from 'material-ui/GridList';
import styles from './ImageCarousel.css';
import {cyan50, grey400} from 'material-ui/styles/colors.js';

const inlineStyles = {
  wrapper: {
    backgroundColor: cyan50
  },
  gridTileSelected: {
    backgroundColor: grey400
  }
};

class ImageTile extends Component {
  constructor(props) {
    super(props);
  }

  handleClick() {
    if (this.props.onChange) {
      this.props.onChange(this.props.url);
    }
  }

  render() {
    return (
      <GridTile
        className={this.props.selected ? styles['grid-tile-selected'] : styles['grid-tile']}
        style={this.props.selected ? inlineStyles.gridTileSelected : null}
        onTouchTap={() => this.handleClick()}
      >
        <img className={styles['grid-tile-image']} src={this.props.url} />
      </GridTile>
    );
  }
}

ImageTile.propTypes = {
  onChange: PropTypes.func,
  selected: PropTypes.bool.isRequired,
  url: PropTypes.string.isRequired
};

export default class ImageCarousel extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      tiles: this.props.images,
      selected: this.props.url || null
    };
  }

  handleChange(url) {
    this.setState({ selected: url });
    if (this.props.onChange) {
      this.props.onChange(url);
    }
  }

  render() {
    return (
      <div className={styles.wrapper} style={inlineStyles.wrapper}>
        <GridList className={styles['grid-list']} cols={2}>
          {this.state.tiles.map((tile) => {
            return (
              <ImageTile key={tile} url={tile} onChange={(url) => this.handleChange(url)} selected={this.state.selected === tile} />
            );
          })}
        </GridList>
      </div>
    );
  }
}

ImageCarousel.propTypes = {
  onChange: PropTypes.func,
  images: PropTypes.array.isRequired,
  url: PropTypes.string
};