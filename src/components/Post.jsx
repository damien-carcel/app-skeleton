import React from 'react';

export default class Post extends React.Component {
  render() {
    const post = this.props.post;

    return (
      <div className="post">
        <div className="title">
          <h1>{post.title}</h1>
        </div>
        <div className="content">
          <p>{post.content}</p>
        </div>
      </div>
    );
  }
}
