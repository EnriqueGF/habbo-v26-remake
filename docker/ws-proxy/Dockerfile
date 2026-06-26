FROM node:20-alpine
WORKDIR /app
RUN npm install ws@8
COPY proxy.cjs /app/proxy.cjs
CMD ["node", "/app/proxy.cjs"]
