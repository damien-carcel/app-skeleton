import React, {ReactNode} from 'react';
import {createUser, deleteUser, listUsers, updateUser, UserData} from '../containers/user';
import {isEmpty} from '../tools/isEmpty';
import Create from './Create';
import User from './User';

interface ListUsersState {
  error: {[key: string]: any};
  isLoaded: boolean;
  users: UserData[];
}

export default class ListUsers extends React.Component<{}, ListUsersState> {
  constructor(props: {}) {
    super(props);

    this.state = {
      error: {},
      isLoaded: false,
      users: [],
    };

    this.delete = this.delete.bind(this);
    this.submit = this.submit.bind(this);
  }

  public componentDidMount(): void {
    this.getAllUsers();
  }

  public delete(userId: string): void {
    this.setState({
      isLoaded: false,
    });

    deleteUser(userId).then(
      () => {
        this.setState((prevState) => ({
          isLoaded: true,
          users: prevState.users.filter((user: UserData) => user.id !== userId),
        }));
      },
      (error) => {
        this.setState({
          error,
          isLoaded: true,
        });
      },
    );
  }

  public submit(userId: string, data: UserData): void {
    this.setState({
      isLoaded: false,
    });

    if (userId) {
      updateUser(userId, data).then(() => {
        this.setState((prevState) => ({
          isLoaded: true,
          users: prevState.users.map((user: UserData) => {
            if (user.id === userId) {
              user.title = data.title;
              user.content = data.content;
            }

            return user;
          }),
        }));
      }, (error) => {
        this.setState({
          error,
          isLoaded: true,
        });
      });
    } else {
      createUser(data).then(() => this.getAllUsers());
    }
  }

  public getAllUsers(): void {
    listUsers().then(
      (result) => {
        this.setState({
          isLoaded: true,
          users: result,
        });
      },
      (error) => {
        this.setState({
          error,
          isLoaded: true,
        });
      },
    );
  }

  public render(): ReactNode {
    const {error, isLoaded, users} = this.state;

    if (!isEmpty(error)) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    const renderedUsers: any[] = users.map((user: UserData) => {
      return <User
        key={user.id}
        user={user}
        handleSubmit={this.submit}
        handleDelete={this.delete}
      />;
    });

    return (
      <div className='container'>
        <Create handleSubmit={this.submit}/>
        <div className='users'>
          {renderedUsers}
        </div>
      </div>
    );
  }
}
