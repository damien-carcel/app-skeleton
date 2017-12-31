import Modal from 'react-modal';
import PostForm from './PostForm';
import PropTypes from "prop-types";
import React from 'react';

export default class Edit extends React.Component {
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
    const postId = this.props.postId;
    const showModal = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Edit</button>
        <Modal isOpen={showModal} contentLabel="Edit a post">
          <PostForm postId={postId}/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}

Edit.propTypes = {
  postId: PropTypes.string
};
