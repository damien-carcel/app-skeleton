import React from 'react';
import backgroundImage from '../../assets/images/background.png';

export default class Background extends React.Component {
  render() {
    return (
      <img src={backgroundImage} />
    );
  }
}
