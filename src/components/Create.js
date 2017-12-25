export default function createButton() {
  return `
    <button class="btn-action btn-create-post" onclick="alert('I get called from a button!')">
      Create a new post
    </button>
  `;
}
