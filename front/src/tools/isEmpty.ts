export const isEmpty = (object: {}) => {
  for (const property in object) {
    if (object.hasOwnProperty(property))
      return false;
  }

  return true;
};
