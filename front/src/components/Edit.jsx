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
    const postId = this.props.postId;
    const showModal = this.state.showModal;

    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Edit</button>
        <Modal isOpen={showModal} contentLabel={"Edit post " + postId}>
          <PostForm postId={postId} handleSubmit={this.handleSubmit}/>
          <button className="btn-action btn-create-post" onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}

Edit.propTypes = {
  handleSubmit: PropTypes.func,
  postId: PropTypes.string
};
