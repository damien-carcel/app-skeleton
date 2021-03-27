import React, { useState } from 'react';
import { getUserCollection, UserData } from '../api/user';
import Users from './Users';
import './App.css';

const App: React.FC = () => {
  const [users, setUsers] = useState<UserData[]>([]);
  const [errorMessage, setErrorMessage] = useState<string>('');

  getUserCollection(1, 10)
    .then((response: Response) => {
      return {
        data: response.json(),
        responseStatus: response.status,
      };
    })
    .then((result: { data: Promise<UserData[]>; responseStatus: number }) => {
      result.data.then((users) => setUsers(users));
    })
    .catch((error) => setErrorMessage(error.message));

  return (
    <div className="App">
      {errorMessage ? (
        <p>Encountered error: &quot{errorMessage}&quot</p>
      ) : (
        <div>
          <Users users={users} />
        </div>
      )}
    </div>
  );
};

export default App;
