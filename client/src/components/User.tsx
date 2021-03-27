import React from 'react';
import { UserData } from '../api/user';

type UserProps = { user: UserData };

const User: React.FC<UserProps> = ({ user }) => {
  return (
    <li>
      {user.email} - {user.firstName} {user.lastName}
    </li>
  );
};

export default User;
