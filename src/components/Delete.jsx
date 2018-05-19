import React from 'react';
import PropTypes from "prop-types";

export default class Delete extends React.Component {
  constructor(props) {
    super(props);

    this.handleDelete = this.handleDelete.bind(this, props.postId);
  }

  handleDelete(postId) {
    this.props.handleDelete(postId);
  }

  render() {
    return (
      <div>
        <button className="btn-action btn-create-post"
                onClick={this.handleDelete}>
          Delete
        </button>
      </div>
    );
  }
}

Delete.propTypes = {
  handleDelete: PropTypes.func,
  postId: PropTypes.string
};
