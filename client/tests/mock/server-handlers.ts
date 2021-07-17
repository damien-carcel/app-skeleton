import { rest } from 'msw';

const handlers = [rest.get('*/api/personToGreet', async (req, res, ctx) => res(ctx.json('World')))];

export { handlers };
