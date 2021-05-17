FROM python:3.8-slim

WORKDIR /install
COPY docker/python/requirements.txt .
RUN pip install -r requirements.txt

WORKDIR /run
COPY python .

ENTRYPOINT ["python3"]
CMD ["app.py"]
