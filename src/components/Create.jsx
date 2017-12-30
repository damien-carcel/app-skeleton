import React from 'react';
import Modal from 'react-modal';

export default class Create extends React.Component {
  constructor () {
    super();
    this.state = {
      showModal: false
    };

    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleCloseModal = this.handleCloseModal.bind(this);
  }

  handleOpenModal () {
    this.setState({ showModal: true });
  }

  handleCloseModal () {
    this.setState({ showModal: false });
  }

  render () {
    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Create a new post</button>
        <Modal isOpen={this.state.showModal} contentLabel="Create a new post">
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
