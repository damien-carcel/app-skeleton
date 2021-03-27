import React from 'react';
import { UserData } from '../api/user';
import User from './User';

type UsersProps = { users: UserData[] };

const Users: React.FC<UsersProps> = ({ users }) => {
  const userItems = users.map((user: UserData) => <User key={user.id} user={user} />);

  return <ul>{userItems}</ul>;
};

export default Users;
