import Modal from 'react-modal';
import PostForm from './PostForm';
import React from 'react';

export default class Create extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      showModal: false
    };

    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleCloseModal = this.handleCloseModal.bind(this);
  }

  handleOpenModal() {
    this.setState({ showModal: true });
  }

  handleCloseModal() {
    this.setState({ showModal: false });
  }

  render() {
    const showModal = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Create a new post</button>
        <Modal isOpen={showModal} contentLabel="Create a new post">
          <PostForm/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
