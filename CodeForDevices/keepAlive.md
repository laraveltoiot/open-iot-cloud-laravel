# setKeepAlive

```C++
#include <WiFi.h>
#include <WiFiClientSecure.h>
#include <PubSubClient.h>

/* ---------- Wi‑Fi ---------- */
const char* WIFI_SSID = "supertest";
const char* WIFI_PASS = "11111111";

/* ---------- MQTT ---------- */
const char* MQTT_HOST = "1aafsdfdsfds.cloud";
const int   MQTT_PORT = 8883;
const char* MQTT_USER = "scfdsifsdfsdories";
const char* MQTT_PASS = "supfsdfsdfds12";

/* ---------- Node identity ---------- */
const char* NODE_UUID = "4105bba9-8570-4b43-a617-c75490e4a41f";
const char* SECRET_KEY = "f719e625-657d-4d89-9f14-7f17f9caf9bb";

/* ---------- MQTT Stack ---------- */
WiFiClientSecure net;
PubSubClient mqtt(net);

/* ---------- State ---------- */
unsigned long lastPing = 0;

void setup() {
  Serial.begin(115200);
  delay(1000);

  WiFi.mode(WIFI_STA);
  WiFi.begin(WIFI_SSID, WIFI_PASS);
  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(300);
    Serial.print(".");
  }
  Serial.println(" connected.");

  net.setInsecure();
  mqtt.setServer(MQTT_HOST, MQTT_PORT);
  mqtt.setKeepAlive(10);  // request PING at least every 10 seconds
}

void loop() {
  if (!mqtt.connected()) {
    reconnect();
  }

  mqtt.loop();

  if (millis() - lastPing > 60000) {
    String topic = "status/" + String(NODE_UUID);
    mqtt.publish(topic.c_str(), "online", true);
    lastPing = millis();
  }
}

void reconnect() {
  while (!mqtt.connected()) {
    Serial.print("Connecting to MQTT...");

    String clientId = "node-" + String(NODE_UUID);
    String willTopic = "status/" + String(NODE_UUID);
    const char* willMessage = "offline";

    bool connected = mqtt.connect(
      clientId.c_str(),
      MQTT_USER,
      MQTT_PASS,
      willTopic.c_str(),
      1,
      true,
      willMessage
    );

    if (connected) {
      Serial.println(" connected.");
      String topic = "status/" + String(NODE_UUID);
      mqtt.publish(topic.c_str(), "online", true);
      lastPing = millis();
    } else {
      Serial.print(" failed, rc=");
      Serial.print(mqtt.state());
      Serial.println(" retrying in 5 seconds");
      delay(5000);
    }
  }
}
```
