import React from 'react';

export default class Button extends React.Component {
  render() {
    return (
      <button onClick={() => alert('I get called from a button!')}>Test</button>
    );
  }
}
