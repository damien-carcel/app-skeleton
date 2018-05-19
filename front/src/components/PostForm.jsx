import React from 'react';
import PropTypes from "prop-types";
import { getPost } from '../containers/posts';

export default class PostForm extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      error: null,
      isLoaded: false,
      title: '',
      content: ''
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  componentDidMount() {
    const postId = this.props.postId;

    if (null !== postId) {
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

  handleInputChange(event) {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    this.setState({
      [name]: value
    });
  }

  handleSubmit(event) {
    event.preventDefault();

    const postId = this.props.postId;
    const data = {
      'title': this.state.title,
      'content': this.state.content,
    };

    this.props.handleSubmit(postId, data);
  }

  render() {
    const { error, isLoaded, title, content } = this.state;
    if (error) {
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
            onChange={this.handleInputChange} />
        </label>
        <br />
        <label>
          Content:
          <textarea
            name="content"
            value={content}
            onChange={this.handleInputChange} />
        </label>
        <input className="btn-action btn-create-post" type="submit" value="Save" />
      </form>
    );
  }
}

PostForm.propTypes = {
  handleSubmit: PropTypes.func,
  postId: PropTypes.string
};

PostForm.defaultProps = {
  postId: null
};
