import React, {ReactNode} from 'react';
import {getUser, UserData} from '../containers/user';
import {isEmpty} from '../tools/isEmpty';

interface UserFormProps {
  handleSubmit: (userId: string, data: UserData) => void;
  userId: string;
}

interface UserFormState {
  content: string;
  error: {[key: string]: any};
  isLoaded: boolean;
  title: string;
  [key: string]: any;
}

export default class UserForm extends React.Component<UserFormProps, UserFormState> {
  constructor(props: UserFormProps) {
    super(props);

    this.state = {
      content: '',
      error: {},
      isLoaded: false,
      title: '',
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
            content: result.content,
            title: result.title,
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
      content: this.state.content,
      id: this.props.userId,
      title: this.state.title,
    };

    this.props.handleSubmit(userId, data);
  }

  public render(): ReactNode {
    const {error, isLoaded, title, content} = this.state;
    if (!isEmpty(error)) {
      return <div>Error: {error.message}</div>;
    }

    if (!isLoaded) {
      return <div>Loading...</div>;
    }

    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          Title:
          <input
            name='title'
            type='text'
            value={title}
            onChange={this.handleInputChange}
          />
        </label>
        <br/>
        <label>
          Content:
          <textarea
            name='content'
            value={content}
            onChange={this.handleInputChange}
          />
        </label>
        <input className='btn-action btn-create-user' type='submit' value='Save'/>
      </form>
    );
  }
}
