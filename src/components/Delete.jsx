import React from 'react';
import PropTypes from "prop-types";
import { deletePost } from '../containers/posts';

export default class Delete extends React.Component {
  constructor(props) {
    super(props);

    this.handleDelete = this.handleDelete.bind(this);
  }

  handleDelete() {
    const postId = this.props.postId;

    deletePost(postId);
  }

  render() {
    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleDelete}>Delete</button>
      </div>
    );
  }
}

Delete.propTypes = {
  postId: PropTypes.string
};
