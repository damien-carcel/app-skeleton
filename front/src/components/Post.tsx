import React from 'react';
import {BlogPostData} from '../containers/posts';
import Delete from './Delete';
import Edit from './Edit';

interface PostProps {
  post: BlogPostData;
  handleDelete: (postId: string) => void;
  handleSubmit: (postId: string, data: BlogPostData) => void;
}

export default function Post(props: PostProps) {
  const post: BlogPostData = props.post;

  return (
    <div className='post'>
      <div className='title'>
        <h1>{post.title}</h1>
      </div>
      <div className='content'>
        <p>{post.content}</p>
      </div>
      <Edit postId={post.id} handleSubmit={props.handleSubmit}/>
      <Delete postId={post.id} handleDelete={props.handleDelete}/>
    </div>
  );
}
