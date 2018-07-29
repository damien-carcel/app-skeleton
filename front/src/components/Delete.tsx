import React, {ReactNode} from 'react';

interface DeleteProps {
  handleDelete: (postId: string) => void;
  postId: string;
}

export default class Delete extends React.Component<DeleteProps, {}> {
  constructor(props: DeleteProps) {
    super(props);
  }

  public handleDelete(postId: string): void {
    this.props.handleDelete(postId);
  }

  public render(): ReactNode {
    return (
      <div>
        <button
          className='btn-action btn-create-post'
          onClick={this.handleDelete.bind(this, this.props.postId)}
        >
          Delete
        </button>
      </div>
    );
  }
}
