import React, {ReactNode} from 'react';
import {getUser, UserData} from '../containers/user';
import {isEmpty} from '../tools/isEmpty';

interface UserFormProps {
  handleSubmit: (userId: string, data: UserData) => void;
  userId: string;
}

interface UserFormState {
  error: {[key: string]: any};
  firstName: string;
  isLoaded: boolean;
  lastName: string;
  username: string;
  [key: string]: any;
}

export default class UserForm extends React.Component<UserFormProps, UserFormState> {
  constructor(props: UserFormProps) {
    super(props);

    this.state = {
      content: '',
      error: {},
      firstName: '',
      isLoaded: false,
      lastName: '',
      username: '',
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  public componentDidMount(): void {
    const userId: string = this.props.userId;

    if (userId) {
      getUser(userId).then(
        (result) => {
          this.setState({
            firstName: result.firstName,
            lastName: result.lastName,
            username: result.username,
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

    this.setState({
      isLoaded: true,
    });
  }

  public handleInputChange(event: any): void {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  public handleSubmit(event: any): void {
    event.preventDefault();

    const userId: string = this.props.userId;
    const data: UserData = {
      firstName: this.state.firstName,
      id: this.props.userId,
      lastName: this.state.lastName,
      username: this.state.username,
    };

    this.props.handleSubmit(userId, data);
  }

  public render(): ReactNode {
    const {error, isLoaded, firstName, lastName, username} = this.state;
    if (!isEmpty(error)) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          <input
            name='firstName'
            type='text'
            value={firstName}
            onChange={this.handleInputChange}
          />
        </label>
        &nbsp;
        <label>
          <input
            name='lastName'
            type='text'
            value={lastName}
            onChange={this.handleInputChange}
          />
        </label>
        <br/>
        <label>
          username:
          <input
            name='username'
            type='text'
            value={username}
            onChange={this.handleInputChange}
          />
        </label>
        <input className='btn-action btn-create-user' type='submit' value='Save'/>
      </form>
    );
  }
}
