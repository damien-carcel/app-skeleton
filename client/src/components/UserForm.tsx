import React, {ReactNode} from 'react';
import {getUser, UserData} from '../containers/user';
import {isEmpty} from '../tools/isEmpty';

interface UserFormProps {
  handleSubmit: (userId: string, data: UserData) => void;
  userId: string;
}

interface UserFormState {
  email: string;
  error: {[key: string]: any};
  firstName: string;
  isLoaded: boolean;
  lastName: string;
  [key: string]: any;
}

export default class UserForm extends React.Component<UserFormProps, UserFormState> {
  constructor(props: UserFormProps) {
    super(props);

    this.state = {
      content: '',
      email: '',
      error: {},
      firstName: '',
      isLoaded: false,
      lastName: '',
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
            email: result.email,
            firstName: result.firstName,
            lastName: result.lastName,
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
      email: this.state.email,
      firstName: this.state.firstName,
      id: this.props.userId,
      lastName: this.state.lastName,
    };

    this.props.handleSubmit(userId, data);
  }

  public render(): ReactNode {
    const {error, isLoaded, firstName, lastName, email} = this.state;
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
          email:
          <input
            name='email'
            type='text'
            value={email}
            onChange={this.handleInputChange}
          />
        </label>
        <input className='btn-action btn-create-user' type='submit' value='Save'/>
      </form>
    );
  }
}
