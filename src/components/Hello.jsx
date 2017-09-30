import React from 'react';
import Background from './Background';
import Button from './Button';

export default class Hello extends React.Component {
  render() {
    return (
      <div className="hello">
        <h1>Hello world</h1>
        <Background />
        <Button />
      </div>
    );
  }
}
