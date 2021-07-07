function getApiUrl(route: string, userId?: string): string {
  const baseUrl = process.env.REACT_APP_API_BASE_URL;

  if (userId) {
    return baseUrl + route.replace('{id}', userId);
  }

  return baseUrl + route;
}

function createHeaders(): Headers {
  const headers = new Headers();
  headers.append('Accept', 'application/ld+json');

  return headers;
}

// function createPostHeaders(): Headers {
//   const headers = createHeaders();
//   headers.append('Content-Type', 'application/ld+json');
//
//   return headers;
// }

export function getPersonToGreet(): Promise<Response> {
  const url = getApiUrl('/api/personToGreet');

  return fetch(url, {
    headers: createHeaders(),
    method: 'GET',
    mode: 'cors',
  });
}
