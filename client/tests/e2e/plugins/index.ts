import { exec } from 'child_process';
import { promisify } from 'util';

const asyncExec = promisify(exec);

export default (on, config) => {
  on('task', {
    'db:populate': ({ firstname, lastname, email, password }) =>
      asyncExec(
        `curl -d '{"firstname": "${firstname}", "lastname":"${lastname}", "email":"${email}", "password":"${password}"}' -H 'Content-Type: application/json' http://client/api/users`
      ),
  });

  return Object.assign({}, config, {
    fixturesFolder: 'tests/e2e/fixtures',
    integrationFolder: 'tests/e2e/specs',
    screenshotsFolder: 'tests/e2e/screenshots',
    videosFolder: 'tests/e2e/videos',
    supportFile: 'tests/e2e/support/index.js',
  });
};
