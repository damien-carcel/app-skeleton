import Modal from 'react-modal';
import PostForm from './PostForm';
import React from 'react';
import PropTypes from "prop-types";

export default class Create extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      showModal: false
    };

    this.handleCloseModal = this.handleCloseModal.bind(this);
    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleCloseModal() {
    this.setState({ showModal: false });
  }

  handleOpenModal() {
    this.setState({ showModal: true });
  }

  handleSubmit(postId, data) {
    this.props.handleSubmit(postId, data);

    this.setState({ showModal: false });
  }

  render() {
    const showModal = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Create a new post</button>
        <Modal isOpen={showModal} contentLabel="Create a new post">
          <PostForm handleSubmit={this.handleSubmit}/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}

Create.propTypes = {
  handleSubmit: PropTypes.func
};
