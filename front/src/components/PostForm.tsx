import React, {ReactNode} from 'react';
import {BlogPostData, getPost} from '../containers/posts';
import {isEmpty} from '../tools/isEmpty';

interface PostFormProps {
  handleSubmit: (postId: string, data: BlogPostData) => void;
  postId: string;
}

interface PostFormState {
  content: string;
  error: {[key: string]: any};
  isLoaded: boolean;
  title: string;
  [key: string]: any;
}

export default class PostForm extends React.Component<PostFormProps, PostFormState> {
  constructor(props: PostFormProps) {
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
    const postId: string = this.props.postId;

    if (postId) {
      getPost(postId).then(
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

    const postId: string = this.props.postId;
    const data: BlogPostData = {
      content: this.state.content,
      id: this.props.postId,
      title: this.state.title,
    };

    this.props.handleSubmit(postId, data);
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
        <input className='btn-action btn-create-post' type='submit' value='Save'/>
      </form>
    );
  }
}
