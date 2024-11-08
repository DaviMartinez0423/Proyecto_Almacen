from pyspark.sql import SparkSession
from pyspark.sql.functions import avg, max, count, col

spark = SparkSession.builder.appName("Accommodation Analysis").getOrCreate()

df = spark.read.csv("/app/airbnb_sales_data.csv", header=True, inferSchema=True)

price_stats = df.groupBy("neighbourhood_group", "room_type") \
    .agg(avg("price").alias("avg_price"), max("price").alias("max_price")) \
    .orderBy("neighbourhood_group", "room_type")
price_stats.coalesce(1).write.csv("/app/output/price_stats", header=True)

availability_stats = df.groupBy("neighbourhood_group") \
    .agg(avg("minimum_nights").alias("avg_minimum_nights"), 
         avg("availability_365").alias("avg_availability_365")) \
    .orderBy("neighbourhood_group")
availability_stats.coalesce(1).write.csv("/app/output/availability_stats", header=True)

reviews_stats = df.groupBy("neighbourhood_group") \
    .agg(avg("rating").alias("avg_rating"), count("number_of_reviews").alias("total_reviews")) \
    .orderBy("neighbourhood_group")
reviews_stats.coalesce(1).write.csv("/app/output/reviews_stats", header=True)

room_type_distribution = df.groupBy("room_type") \
    .count() \
    .orderBy("count", ascending=False)
room_type_distribution.coalesce(1).write.csv("/app/output/room_type_distribution", header=True)

price_outliers = df.groupBy("neighbourhood_group") \
    .agg(max("price").alias("max_price"), avg("price").alias("avg_price"), min("price").alias("min_price")) \
    .orderBy("neighbourhood_group")
price_outliers.coalesce(1).write.csv("/app/output/price_outliers", header=True)

monthly_reviews = df.groupBy("neighbourhood_group", "room_type") \
    .agg(avg("reviews_per_month").alias("avg_reviews_per_month")) \
    .orderBy("neighbourhood_group", "room_type")
monthly_reviews.coalesce(1).write.csv("/app/output/monthly_reviews", header=True)

spark.stop()
