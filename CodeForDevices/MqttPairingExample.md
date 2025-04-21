# MqttPairing Example

## MQTT Pairing Flow (ESP32 ↔ Laravel)
Securely link an IoT device (like ESP32) to a user account in the Laravel IoT Cloud platform using MQTT messages.

🧩 Components

- Node: Represents a physical device in the database.
- UserNodeMapping: A pivot table that maps users to nodes, includes status and a secret_key.
- MQTT Broker: (e.g. HiveMQ Cloud) used for communication.
- Laravel Listener Command: Subscribes to pairing topics and updates the mapping status.

🔐 Pairing Steps
1. Admin Creates a Node
   - From browser or API. 
   - Admin assigns one or more users. 
   - Laravel creates a UserNodeMapping record with:
     - status = requested 
     - secret_key = UUID

2. ESP32 Connects
   - Device connects to WiFi + MQT
- Publishes on topic: `node/<NODE_UUID>/user/mapping`

```json
{
"secret_key": "abcd-1234-efgh-5678"
}

```

3. Laravel Listener Handles Pairing
- Subscribed to: node/+/user/mapping
- If node_uuid + secret_key + status=requested match → status becomes confirmed.

4. ✅ Node Is Now Paired
- Pairing is complete.
- App can now allow full communication, control, telemetry etc.

## Arduino Nano ESP32 Example
```c++
#include <WiFi.h>
#include <PubSubClient.h>
#include <WiFiClientSecure.h>

// Wi-Fi credentials
const char* ssid = "supertest";
const char* password = "11111111";

// HiveMQ Cloud broker
const char* mqtt_server = "1aafgsdfsdfds7d3.s1.eu.hivemq.cloud";
const int mqtt_port = 8883;
const char* mqtt_username = "scienceStories";
const char* mqtt_password = "fsdfsdfds";

// Secure WiFi client (TLS)
WiFiClientSecure espClient;
PubSubClient client(espClient);

void setup_wifi() {
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

  Serial.print("Connecting to WiFi");
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected.");
}

void reconnect() {
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    String clientId = "ESP32Client-" + String(random(0xffff), HEX);
    if (client.connect(clientId.c_str(), mqtt_username, mqtt_password)) {
      Serial.println("connected");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      delay(5000);
    }
  }
}

void setup() {
  Serial.begin(115200);
  setup_wifi();
  espClient.setInsecure();

  client.setServer(mqtt_server, mqtt_port);
}

void loop() {
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  if (Serial.available() > 0) {
    String message = Serial.readStringUntil('\n');
    message.trim();

    client.publish("commands/serialMessage", message.c_str());
    Serial.print("Published: ");
    Serial.println(message);
  }
}
```
