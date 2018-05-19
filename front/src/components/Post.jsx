import Delete from './Delete';
import Edit from './Edit';
import PropTypes from "prop-types";
import React from 'react';

export default function Post(props) {
  const post = props.post;

  return (
    <div className="post">
      <div className="title">
        <h1>{post.title}</h1>
      </div>
      <div className="content">
        <p>{post.content}</p>
      </div>
      <Edit postId={post.id} handleSubmit={props.handleSubmit}/>
      <Delete postId={post.id} handleDelete={props.handleDelete}/>
    </div>
  );
}

Post.propTypes = {
  post: PropTypes.objectOf(PropTypes.string),
  handleDelete: PropTypes.func,
  handleSubmit: PropTypes.func
};
