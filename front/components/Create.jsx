import React from 'react';
import ReactModal from 'react-modal';

export default class Create extends React.Component {
  constructor () {
    super();
    this.state = {
      showModal: false,
      title: '',
      content: ''
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

  handleTitleChange(event) {
    this.setState({title: event.target.title});
  }

  handleContentChange(event) {
    this.setState({title: event.target.title});
  }

  handleSubmit() {
    alert('I get called from a button!');
  }

  render () {
    return (
      <div>
        <button className="btn-action btn-create-post" onClick={this.handleOpenModal}>Create a new post</button>
        <ReactModal
          isOpen={this.state.showModal}
          contentLabel="Create a new post"
          ariaHideApp={false}
        >
          <button className="btn-action" onClick={this.handleCloseModal}>Cancel</button>
        </ReactModal>
      </div>
    );
  }
}
