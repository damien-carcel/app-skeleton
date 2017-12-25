import React from 'react';

export default class Create extends React.Component {
  constructor () {
    super();

    this.handleClick = this.handleClick.bind(this);
  }

  handleClick() {
    alert('I get called from a button!');
  }

  render () {
    return (
      <button className="btn-action btn-create-post" onClick={this.handleClick}>Create a new post</button>
    );
  }
}
