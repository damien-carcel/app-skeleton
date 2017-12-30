import React from 'react';
import { createPosts } from '../containers/posts';

export default class PostForm extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      title: '',
      content: ''
    };

    this.handleInputChange = this.handleInputChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleInputChange(event) {
    const target = event.target;
    const value = target.value;
    const name = target.name;

    this.setState({
      [name]: value
    });
  }

  handleSubmit() {
    const data = {
      'title': this.state.title,
      'content': this.state.content,
    };

    createPosts(data);
  }

  render() {
    return (
      <form onSubmit={this.handleSubmit}>
        <label>
          Title:
          <input
            name="title"
            type="text"
            value={this.state.title}
            onChange={this.handleInputChange} />
        </label>
        <br />
        <label>
          Content:
          <textarea
            name="content"
            value={this.state.content}
            onChange={this.handleInputChange} />
        </label>
        <input className="btn-action btn-create-post" type="submit" value="Save" />
      </form>
    );
  }
}
