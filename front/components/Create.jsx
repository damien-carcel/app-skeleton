import React from 'react';

export default class Create extends React.Component {
  render() {
    return (
      <button onClick={() => alert('I get called from a button!')}>Create a new post</button>
    );
  }
}
