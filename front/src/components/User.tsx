import React from 'react';
import {UserData} from '../containers/user';
import Delete from './Delete';
import Edit from './Edit';

interface UserProps {
  user: UserData;
  handleDelete: (userId: string) => void;
  handleSubmit: (userId: string, data: UserData) => void;
}

export default function User(props: UserProps) {
  const user: UserData = props.user;

  return (
    <div className='user'>
      <div className='title'>
        <h1>{user.title}</h1>
      </div>
      <div className='content'>
        <p>{user.content}</p>
      </div>
      <Edit userId={user.id} handleSubmit={props.handleSubmit}/>
      <Delete userId={user.id} handleDelete={props.handleDelete}/>
    </div>
  );
}
