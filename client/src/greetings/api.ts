export const url = (route: string, userId?: string) => {
  const baseUrl = process.env.REACT_APP_API_BASE_URL;

  if (userId) {
    return baseUrl + route.replace('{id}', userId);
  }

  return baseUrl + route;
};

export const createHeaders = () => {
  const headers = new Headers();
  headers.append('Accept', 'application/json');

  return headers;
};

export const createPostHeaders = () => {
  const headers = createHeaders();
  headers.append('Content-Type', 'application/json');

  return headers;
};
