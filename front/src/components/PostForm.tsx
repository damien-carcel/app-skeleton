import React, {ReactNode} from 'react';
import {BlogPostData, getPost} from '../containers/posts';
import {isEmpty} from "../tools/isEmpty";

interface PostFormProps {
  handleSubmit: Function,
  postId: string,
}

interface PostFormState {
  error: {[key: string]: any},
  isLoaded: boolean,
  title: string,
  content: string,
  [key: string]: any,
}

export default class PostForm extends React.Component<PostFormProps, PostFormState> {
  constructor(props: PostFormProps) {
    super(props);

    this.state = {
      error: {},
      isLoaded: false,
      title: '',
      content: '',
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount(): void {
    const postId: string = this.props.postId;

    if (postId) {
      getPost(postId).then(
        (result) => {
          this.setState({
            title: result.title,
            content: result.content
          });
        },
        (error) => {
          this.setState({
            isLoaded: true,
            error: error
          });
        }
      );
    }

    this.setState({
      isLoaded: true
    });
  }

  handleInputChange(event: any): void {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    this.setState({
      [name]: value,
    });
  }

  handleSubmit(event: any): void {
    event.preventDefault();

    const postId: string = this.props.postId;
    const data: BlogPostData = {
      'id': this.props.postId,
      'title': this.state.title,
      'content': this.state.content,
    };

    this.props.handleSubmit(postId, data);
  }

  render(): ReactNode {
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
            name="title"
            type="text"
            value={title}
            onChange={this.handleInputChange}/>
        </label>
        <br/>
        <label>
          Content:
          <textarea
            name="content"
            value={content}
            onChange={this.handleInputChange}/>
        </label>
        <input className="btn-action btn-create-post" type="submit" value="Save"/>
      </form>
    );
  }
}
