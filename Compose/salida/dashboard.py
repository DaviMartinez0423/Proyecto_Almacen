from flask import Flask, render_template
import pandas as pd

app = Flask(__name__)

@app.route('/')
def index():
    # Cargar los resultados del análisis
    data = pd.read_csv("output/review_count.csv")
    records = data.to_dict(orient="records")
    return render_template("index.html", records=records)

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000)
