import React, {ReactNode} from 'react';
import Modal from 'react-modal';
import {UserData} from '../containers/user';
import UserForm from './UserForm';

interface CreateProps {
  handleSubmit: (userId: string, data: UserData) => void;
}

interface CreateState {
  showModal: boolean;
}

export default class Create extends React.Component<CreateProps, CreateState> {
  constructor(props: CreateProps) {
    super(props);

    this.state = {
      showModal: false,
    };

    this.handleCloseModal = this.handleCloseModal.bind(this);
    this.handleOpenModal = this.handleOpenModal.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  public handleCloseModal(): void {
    this.setState({showModal: false});
  }

  public handleOpenModal(): void {
    this.setState({showModal: true});
  }

  public handleSubmit(userId: string, data: UserData): void {
    this.props.handleSubmit(userId, data);

    this.setState({showModal: false});
  }

  public render(): ReactNode {
    const showModal: boolean = this.state.showModal;

    return (
      <div>
        <button className='btn-action btn-create-user' onClick={this.handleOpenModal}>Create a new user</button>
        <Modal isOpen={showModal} contentLabel='Create a new user'>
          <UserForm userId={''} handleSubmit={this.handleSubmit}/>
          <button className='btn-action btn-create-user' onClick={this.handleCloseModal}>Cancel</button>
        </Modal>
      </div>
    );
  }
}
